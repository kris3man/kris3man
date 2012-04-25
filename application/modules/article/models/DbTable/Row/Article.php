<?php
/**
 * Article.php
 *
 * Copyright (c) 2011 Shaun Freeman <shaun@shaunfreeman.co.uk>.
 *
 * This file is part of SF.
 *
 * SF is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SF is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with SF.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category   SF
 * @package    Article
 * @subpackage Model_DbTable_Row
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Database class for the Article table row.
 *
 * @category   SF
 * @package    Article
 * @subpackage Model_DbTable_Row
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Article_Model_DbTable_Row_Article extends ZendSF_Model_DbTable_Row_Abstract
{
    /**
     * @var array
     */
    private $_params;

    public function getParams()
    {
        if (null === $this->_params) {
            $this->_params = parse_ini_string($this->getRow()->params);
        }

        return $this;
    }

    public function getParam($param)
    {
        if (null === $this->_params) {
            $this->getParams();
        }

        return $this->_params[$param];
    }

    public function getCdate($format = 'EEEE, dd MMMM YYYY h:mm a')
    {
        $date = new Zend_Date($this->getRow()->cdate);
        return $date->toString($format);
    }

    public function getMdate($format = 'EEEE, dd MMMM YYYY h:mm a')
    {
        $date = new Zend_Date($this->getRow()->mdate);
        return $date->toString($format);
    }
}
