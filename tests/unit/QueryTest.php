<?php
/**
 * @link http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\db\mysql\tests;

use yii\db\Expression;
use yii\db\Query;

/**
 * @group db
 * @group mysql
 */
class QueryTest extends \yii\db\tests\unit\QueryTest
{
    protected $driverName = 'mysql';

    /**
     * Tests MySQL specific syntax for index hints.
     */
    public function testQueryIndexHint()
    {
        $db = $this->getConnection();

        $query = (new Query())->from([new Expression('{{%customer}} USE INDEX (primary)')]);
        $row = $query->one($db);
        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('name', $row);
        $this->assertArrayHasKey('email', $row);
    }

    public function testLimitOffsetWithExpression()
    {
        $query = (new Query())->from('customer')->select('id')->orderBy('id');
        // In MySQL limit and offset arguments must both be nonnegative integer constant
        $query
            ->limit(new Expression('2'))
            ->offset(new Expression('1'));

        $result = $query->column($this->getConnection());

        $this->assertCount(2, $result);

        $this->assertNotContains(1, $result);
        $this->assertContains(2, $result);
        $this->assertContains(3, $result);
    }
}
