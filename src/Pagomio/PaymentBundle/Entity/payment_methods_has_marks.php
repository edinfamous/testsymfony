<?php

namespace Pagomio\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * payment_methods_has_marks
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pagomio\PaymentBundle\Entity\payment_methods_has_marksRepository")
 */
class payment_methods_has_marks
{

    /**
     * @var integer
     * 
     * @ORM\Column(name="payment_method_id", type="integer")
     */
    private $paymentMethodId;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="marks_id", type="integer")
     */
    private $marksId;

    /**
     * @var float
     *
     * @ORM\Column(name="commission", type="float")
     */
    private $commission;

    /**
     * Set paymentMethodId
     *
     * @param integer $paymentMethodId
     * @return payment_methods_has_marks
     */
    public function setPaymentMethodId($paymentMethodId)
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    /**
     * Get paymentMethodId
     *
     * @return integer 
     */
    public function getPaymentMethodId()
    {
        return $this->paymentMethodId;
    }

    /**
     * Set marksId
     *
     * @param integer $marksId
     * @return payment_methods_has_marks
     */
    public function setMarksId($marksId)
    {
        $this->marksId = $marksId;

        return $this;
    }

    /**
     * Get marksId
     *
     * @return integer 
     */
    public function getMarksId()
    {
        return $this->marksId;
    }

    /**
     * Set commission
     *
     * @param float $commission
     * @return payment_methods_has_marks
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission
     *
     * @return float 
     */
    public function getCommission()
    {
        return $this->commission;
    }
}
