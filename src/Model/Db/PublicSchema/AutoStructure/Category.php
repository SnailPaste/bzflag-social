<?php
/**
 * This file has been automatically generated by Pomm's generator.
 * You MIGHT NOT edit this file as your changes will be lost at next
 * generation.
 */

namespace App\Model\Db\PublicSchema\AutoStructure;

use PommProject\ModelManager\Model\RowStructure;

/**
 * Category
 *
 * Structure class for relation public.category.
 * 
 * Class and fields comments are inspected from table and fields comments.
 * Just add comments in your database and they will appear here.
 * @see http://www.postgresql.org/docs/9.0/static/sql-comment.html
 *
 *
 *
 * @see RowStructure
 */
class Category extends RowStructure
{
    /**
     * __construct
     *
     * Structure definition.
     *
     * @access public
     */
    public function __construct()
    {
        $this
            ->setRelation('public.category')
            ->setPrimaryKey(['id'])
            ->addField('id', 'int4')
            ->addField('parent_category', 'int4')
            ->addField('display_order', 'int4')
            ->addField('name', 'varchar')
            ->addField('slug', 'varchar')
            ->addField('description', 'text')
            ->addField('topics', 'int4')
            ->addField('posts', 'int4')
            ;
    }
}
