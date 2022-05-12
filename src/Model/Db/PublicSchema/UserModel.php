<?php

namespace App\Model\Db\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\Db\PublicSchema\AutoStructure\User as UserStructure;
use App\Model\Db\PublicSchema\User;

/**
 * UserModel
 *
 * Model class for table user.
 *
 * @see Model
 */
class UserModel extends Model
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
        $this->structure = new UserStructure;
        $this->flexible_entity_class = '\App\Model\Db\PublicSchema\User';
    }
}
