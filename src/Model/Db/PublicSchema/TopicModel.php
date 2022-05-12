<?php

namespace App\Model\Db\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\Db\PublicSchema\AutoStructure\Topic as TopicStructure;
use App\Model\Db\PublicSchema\Topic;

/**
 * TopicModel
 *
 * Model class for table topic.
 *
 * @see Model
 */
class TopicModel extends Model
{
    use WriteQueries;

    /**
     * __construct()
     *
     * Model constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->structure = new TopicStructure;
        $this->flexible_entity_class = '\App\Model\Db\PublicSchema\Topic';
    }

    public function findWithUsersByCategory(int $category_id, int $page, int $posts_per_page)
    {
        $user_model = $this->getSession()->getModel(UserModel::class);

        $sql = "SELECT {projection} FROM {topic} t LEFT OUTER JOIN {user} u1 ON t.author = u1.id LEFT OUTER JOIN {user} u2 ON t.last_reply_author = u2.id WHERE category = $*";

        $projection = $this->createProjection()
            ->setField("author_name", 'u1.display_name AS author_name', 'text')
            ->setField("last_reply_author_name", 'u2.display_name AS last_reply_author_name', 'text')
        ;

        $sql = strtr(
            $sql,
            [
                '{topic}' => $this->structure->getRelation(),
                '{user}' => $user_model->getStructure()->getRelation(),
                '{projection}' => $projection->formatFields('t')
            ]
        );

        $count = $this->countWhere('category = $*', [$category_id]);

        return $this->paginateQuery($sql, [$category_id], $count, $posts_per_page, $page);
        //return $this->query($sql, [$category_id], $projection);
    }
}
