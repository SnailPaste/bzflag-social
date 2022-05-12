<?php

namespace App\Model\Db\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\Db\PublicSchema\AutoStructure\Report as ReportStructure;
use App\Model\Db\PublicSchema\Report;

/**
 * ReportModel
 *
 * Model class for table report.
 *
 * @see Model
 */
class ReportModel extends Model
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
        $this->structure = new ReportStructure;
        $this->flexible_entity_class = '\App\Model\Db\PublicSchema\Report';
    }
}
