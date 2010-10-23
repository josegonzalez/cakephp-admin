---
title: "<?php echo $alias ?> action"
---

    function <?php echo $alias; ?>() {
        $<?php echo $singularName . Inflector::pluralize(Inflector::humanize($alias)); ?> = $this-><?php echo $currentModelName; ?>->findLog();
        $this->set(compact('<?php echo $singularName . Inflector::pluralize(Inflector::humanize($alias)); ?>'));
    }