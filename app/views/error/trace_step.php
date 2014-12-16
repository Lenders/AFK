<tr>
    <td><?php echo $key?></td>
    <td><?php echo isset($file) ? '<a href="' . $this->getFileUrl($file, $line) . '" target="_blank">' . $this->pathToRelative($file) . '</a>' : '{}'?></td>
    <td><?php echo isset($line) ? $line : '-'?></td>
    <td>
        <?php echo $function?>(
            <?php echo empty($args) ? 'void' : $this->exportArgsArray($args)?>
        )
    </td>
</tr>