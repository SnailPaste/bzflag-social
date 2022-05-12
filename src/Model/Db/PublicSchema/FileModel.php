<?php

namespace App\Model\Db\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\Db\PublicSchema\AutoStructure\File as FileStructure;
use App\Model\Db\PublicSchema\File;

/**
 * FileModel
 *
 * Model class for table file.
 *
 * @see Model
 */
class FileModel extends Model
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
        $this->structure = new FileStructure;
        $this->flexible_entity_class = '\App\Model\Db\PublicSchema\File';
    }
}
