<?php

namespace App\Model\Db\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\Db\PublicSchema\AutoStructure\Post as PostStructure;
use App\Model\Db\PublicSchema\Post;

/**
 * PostModel
 *
 * Model class for table post.
 *
 * @see Model
 */
class PostModel extends Model
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
        $this->structure = new PostStructure;
        $this->flexible_entity_class = '\App\Model\Db\PublicSchema\Post';
    }

    public function findWithUserInfoByTopic($topic_id)
    {
        $user_model = $this->getSession()->getModel(UserModel::class);

        $sql = "SELECT {projection} FROM {post} t LEFT OUTER JOIN {user} u ON t.author = u.id WHERE topic = $*";

        $projection = $this->createProjection()
            ->setField("author_name", 'u.display_name AS author_name', 'text')
            ->setField("author_has_signature", '(CHAR_LENGTH(u.signature) > 0) AS author_has_signature', 'bool')
            ->setField("author_signature_xml", 'u.signature_xml AS author_signature_xml', 'text')
            ->setField("author_posts", 'u.posts AS author_posts', 'int4')
            ->setField("author_registration_date", 'u.when_created AS author_registration_date', 'text')
        ;

        $sql = strtr(
            $sql,
            [
                '{post}' => $this->structure->getRelation(),
                '{user}' => $user_model->getStructure()->getRelation(),
                '{projection}' => $projection->formatFields('t')
            ]
        );

        return $this->query($sql, [$topic_id], $projection);
    }
}
