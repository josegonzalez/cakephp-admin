---
title: "<?php echo $alias; ?> action",
finders: ["<?php echo $alias; ?>"]
---

    function <?php echo $alias; ?>() {
        $this->paginate = array('<?php echo $alias; ?>');
        $<?php echo $pluralName ?> = $this->paginate();
        $this->set(compact('<?php echo $pluralName ?>'));
    }