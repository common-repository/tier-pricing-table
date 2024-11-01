<?php

namespace TierPricingTable\Admin\Notifications\Notifications;

use TierPricingTable\Core\ServiceContainerTrait;
/**
 * Class Notifications
 *
 * @package TierPricingTable\Admin\Notifications
 */
abstract class BlackFriday extends Notification {
    use ServiceContainerTrait;
    public function getTemplate() : string {
        return 'admin/notifications/black-friday.php';
    }

    public function getId() : string {
        return 'black-friday-' . $this->getBlackFridayDate();
    }

    public abstract function getBlackFridayDate() : string;

    public function isBlackFriday() : bool {
        $blackFriday = $this->getBlackFridayDate();
        $start = strtotime( "{$blackFriday} - 3 days" );
        $end = strtotime( "{$blackFriday} + 7 days" );
        return time() > $start && time() < $end;
    }

    public function passedRequirements() : bool {
        return $this->isBlackFriday();
    }

    public function isActive() : bool {
        return $this->passedRequirements() && !$this->isSeen();
    }

}
