<?php
if(isset($var_js))
{
    foreach ($var_js as $row)
    {
        echo "var {$row['name']} = {$row['value']};\n";
    }
}
