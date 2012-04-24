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
 * @subpackage Model_DbTable
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */

/**
 * Database adapter class for the Article table.
 *
 * @category   SF
 * @package    Article
 * @subpackage Model_DbTable
 * @copyright  Copyright (c) 2011 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license    http://www.gnu.org/licenses GNU General Public License
 * @author     Shaun Freeman <shaun@shaunfreeman.co.uk>
 */
class Article_Model_DbTable_Article extends ZendSF_Model_DbTable_Abstract
{
    /**
     * @var string database table
     */
    protected $_name = 'article';

    /**
     * @var string primary key
     */
    protected $_primary = 'articleId';

    /**
     * @var string row class
     */
    protected $_rowClass = 'Article_Model_DbTable_Row_Article';

    /**
     * @var array Reference map for parent tables
     */
    protected $_referenceMap = array();

    public function getArticleById($id)
    {
        return $this->find($id)->current();
    }

    public function getArticleByIdent($ident)
    {
        $select = $this->select()->where('ident = ?', $ident);
        return $this->fetchRow($select);

    }
}
