<?php

namespace App\Model\Db\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\Db\PublicSchema\AutoStructure\Rank as RankStructure;
use App\Model\Db\PublicSchema\Rank;

/**
 * RankModel
 *
 * Model class for table rank.
 *
 * @see Model
 */
class RankModel extends Model
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
        $this->structure = new RankStructure;
        $this->flexible_entity_class = '\App\Model\Db\PublicSchema\Rank';
    }
}
