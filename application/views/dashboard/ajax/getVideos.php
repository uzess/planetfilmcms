
<?php

foreach ($videos as $vid)
{
    echo "<option value='" . $vid->id . "'>" . $vid->name . "</option>";
}