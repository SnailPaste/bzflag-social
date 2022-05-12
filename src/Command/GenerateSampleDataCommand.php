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

namespace App\Command;

use App\Model\Db\PublicSchema\Category;
use App\Model\Db\PublicSchema\Post;
use App\Model\Db\PublicSchema\Rank;
use App\Model\Db\PublicSchema\Topic;
use App\Model\Db\PublicSchema\User;
use PommProject\Foundation\Pomm;
use s9e\TextFormatter\Bundles\Forum as TextFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateSampleDataCommand extends Command
{
    protected static $defaultName = 'dev:generate-sample-data';

    private $pomm;

    public function __construct(Pomm $pomm)
    {
        parent::__construct();

        $this->pomm = $pomm;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $kernel = $this->getApplication()->getKernel();
        $env = $kernel->getEnvironment();
        if ('dev' !== $env && 'test' !== $env) {
            $output->writeln('This command only runs for dev and test environments');

            return Command::FAILURE;
        }

        $rank_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\RankModel::class);
        $user_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\UserModel::class);
        $category_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\CategoryModel::class);
        $topic_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\TopicModel::class);
        $post_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\PostModel::class);
        $file_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\FileModel::class);
        $report_model = $this->pomm['db']->getModel(\App\Model\Db\PublicSchema\ReportModel::class);

        // Count total rows
        $total_rows = $rank_model->countWhere('1=1') + $user_model->countWhere('1=1') +
            $category_model->countWhere('1=1') + $topic_model->countWhere('1=1') + $post_model->countWhere('1=1') +
            $file_model->countWhere('1=1') + $report_model->countWhere('1=1');

        if ($total_rows > 0) {
            $output->writeln('The database tables are not empty. Bailing out.');

            return Command::FAILURE;
        }

        // Create ranks
        $rank_keys = [
            'id', 'title', 'image', 'special', 'min_posts',
        ];
        $ranks = [
            [1, 'Recruit', 'recruit.png', false, 0],
            [2, 'Private', 'private.png', false, 1],
            [3, 'Private First Class', 'pfc.png', false, 5],
            [4, 'Administrator', 'admin.png', true, 0],
            [5, 'Developer', 'dev.png', true, 0],
        ];
        foreach ($ranks as $rank_values) {
            $rank = new Rank(array_combine($rank_keys, $rank_values));
            $rank_model->insertOne($rank);
        }

        // Create users
        $user_keys = [
            'id', 'external_id', 'display_name', 'rank', 'email', 'signature', 'signature_xml', 'locale', 'timezone',
            'topics', 'posts', 'registration_ip', 'last_ip', 'when_created', 'last_visit', 'last_post',
        ];
        $users = [
            [
                1, 'fb22afba-4500-4347-b93d-8af7a91a02f1', 'Some Admin', 4, 'admin@example.test', '[b]I\'m an admin! Weeee![/b]', '',
                'en-us', 'America/Chicago', 0, 0, '1.2.3.4', null, new \DateTime('2022-01-01 04:04:04'), null, null,
            ],
            [
                2, '3acabae0-ee01-4e54-b3e5-cb901c1a9f08', 'Some Body', null, 'some.body@example.test', '[u]Look at me![/u]', '',
                'fr', 'Europe/Rome', 1, 1, '4.4.4.4', '4.4.4.4', new \DateTime('2022-01-07 08:01:00'), new \DateTime('2022-03-14 05:51:23'), new \DateTime('2022-01-07 09:01:00'),
            ],
            [
                3, 'a6f88706-970d-4843-ba34-2183a6da0497', 'Player 1', null, 'player1@example.test', '[i]Ready Player 1, ROFLOL!![/i]', '',
                'en-us', 'UTC', 0, 1, '10.1.0.1', '::1', new \DateTime('2022-01-09 22:13:54'), new \DateTime('2022-01-14 20:11:34'), new \DateTime('2022-01-12 05:44:21'),
            ],
        ];
        foreach ($users as $user_values) {
            $data = array_combine($user_keys, $user_values);
            $data['signature_xml'] = TextFormatter::parse($data['signature']);
            $user = new User($data);
            $user_model->insertOne($user);
        }

        // Create categories
        $category_keys = [
            'id', 'parent_category', 'display_order', 'name', 'slug', 'description', 'topics', 'posts',
        ];
        $categories = [
            [1, null, 1, 'Project News', 'project-news', 'Get the latest news for ACME Widgets!', 0, 0],
            [2, null, 3, 'Players', 'players', '', 1, 2],
            [3, 2, 5, 'General Discussion', 'general-discussion', '', 1, 2],
            [4, 2, 4, 'Screenshots & Artwork', 'screenshots-art', '', 0, 0],
            [5, null, 2, 'Servers', 'servers', '', 0, 0],
            [6, 5, 6, 'Servers: General Discussion', 'servers-general-discussion', '', 0, 0],
            [7, 5, 8, 'Server Setup and Administration', 'server-setup-and-administration', '', 0, 0],
            [8, 5, 7, 'Plugin-In Releases', 'plugin-in-releases', '', 0, 0],
        ];
        foreach ($categories as $category_values) {
            $category = new Category(array_combine($category_keys, $category_values));
            $category_model->insertOne($category);
        }

        // Create topics
        $topic_keys = [
            'id', 'category', 'title', 'author', 'when_created', 'last_reply_author', 'last_reply',
        ];
        $topics = [
            [1, 3, 'Welcome to the new forum', 2, new \DateTime('2022-01-07 09:01:00'), 3, new \DateTime('2022-01-12 05:44:21')],
        ];
        foreach ($topics as $topic_values) {
            $topic = new Topic(array_combine($topic_keys, $topic_values));
            $topic_model->insertOne($topic);
        }

        // Create posts
        $post_keys = [
            'id', 'topic', 'author', 'body', 'body_xml', 'when_created', 'last_edited',
        ];
        $posts = [
            [1, 1, 2, '[b]Welcome![/b]', '', new \DateTime('2022-01-07 09:01:00'), null],
            [2, 1, 3, 'Why [i]hello[/i] there!', '', new \DateTime('2022-01-12 05:44:21'), null],
        ];
        foreach ($posts as $post_values) {
            $data = array_combine($post_keys, $post_values);
            $data['body_xml'] = TextFormatter::parse($data['body']);
            $post = new Post($data);
            $post_model->insertOne($post);
        }

        // TODO: Add sample data for files and reports

        // TODO: Verify that the data was inserted correctly? We could count how many records exist in each table.

        $output->writeln('The sample data has been created successfully.');

        return Command::SUCCESS;
    }
}
