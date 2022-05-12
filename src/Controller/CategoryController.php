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

class CategoryController extends AbstractController
{
    protected $pomm;
    protected $category_model;

    public function __construct(Pomm $pomm)
    {
        $this->pomm = $pomm;
        $this->category_model = $pomm['db']->getModel(\App\Model\Db\PublicSchema\CategoryModel::class);
    }

    public function view(Request $request, string $slug = null, $page = 1): Response
    {
        // If $categoryID is 0, we're loading the index page
        if (null === $slug) {
            // Fetch any categories that have no parent
            $childCategories = $this->category_model->findWhere('parent_category IS NULL');

            return $this->render('category/view.html.twig', [
                'category' => null,
                'childCategories' => $childCategories,
            ]);
        }
        // Attempt to fetch the category and any posts under that category
        else {
            try {
                $category = $this->category_model->findWhere('slug = $*', [$slug])->get(0);
            }
            catch (\OutOfBoundsException $e) {
                // If it wasn't found, redirect to the index
                return $this->redirectToRoute('index');
            }

            // Fetch any child categories that are directly under this category
            $childCategories = $this->category_model->findWhere('parent_category = $*', [$category->id]);

            $topic_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\TopicModel::class);
            $topics = $topic_model->findWithUsersByCategory($category->getId(), $page, 15);

            return $this->render('category/view.html.twig', [
                'category' => $category,
                'childCategories' => $childCategories,
                'topics' => $topics->getIterator(),
                'category_page' => $topics->getPage(),
                'category_pages' => $topics->getLastPage(),
            ]);
        }
    }
}
