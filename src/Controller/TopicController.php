<?php

declare(strict_types=1);
/*
 * BZFlag Social - Simple forum and messaging website for BZFlag
 * Copyright (C) 2022  Snail Paste, LLC
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Controller;

use PommProject\Foundation\Pomm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TopicController extends AbstractController
{
    protected $pomm;
    protected $topic_model;

    public function __construct(Pomm $pomm)
    {
        $this->pomm = $pomm;
        $this->topic_model = $pomm['db']->getModel(\App\Model\Db\PublicSchema\TopicModel::class);
    }

    public function view(Request $request, string $slug, int $topicID, $page = 1): Response
    {
        // Try to find the category
        try {
            $category_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\CategoryModel::class);
            $category = $category_model->findWhere('slug = $*', [$slug])->get(0);
        }
        catch (\OutOfBoundsException $e) {
            // If it wasn't found, redirect to the index
            return $this->redirectToRoute('index');
        }

        $topic = $this->topic_model->findByPK(['id' => $topicID]);

        // If it wasn't found, redirect to the category
        if (null === $topic) {
            return $this->redirectToRoute('category_view', ['slug' => $slug]);
        }

        // Fetch any posts in this topic, paginating as needed
        // TODO: Pagination
        $post_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\PostModel::class);
        $posts = $post_model->findWithUserInfoByTopic($topic->id);

        return $this->render('topic/view.html.twig', [
            'category' => $category,
            'topic' => $topic,
            'posts' => $posts,
        ]);
    }
}
