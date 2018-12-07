<?php

use PHPUnit\Framework\TestCase;

final class BuildTableTest extends TestCase
{
    public function test()
    {
        include_once 'buildTable.php';
        // if nothing is there to replace, an empty string will be returned.
        $this->assertEquals('<table summary="summary" border="0" cellpadding="0" cellspacing="0"><tbody>
 <tr><td class="tableclass">foo
 </td></tr>
</tbody></table>',
            buildTable('foo', 'tableclass', 'summary'));
    }
}
