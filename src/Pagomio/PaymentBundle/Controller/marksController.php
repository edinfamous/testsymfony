<?php

namespace Pagomio\PaymentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagomio\PaymentBundle\Entity\marks;
use Pagomio\PaymentBundle\Entity\payment_methods_has_marks;
use Pagomio\PaymentBundle\Form\marksType;

/**
 * marks controller.
 *
 * @Route("/marks")
 */
class marksController extends Controller
{

    /**
     * Lists all marks entities.
     *
     * @Route("/", name="marks")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PagomioPaymentBundle:marks')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new marks entity.
     *
     * @Route("/", name="marks_create")
     * @Method("POST")
     * @Template("PagomioPaymentBundle:marks:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new marks();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('marks_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a marks entity.
     *
     * @param marks $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(marks $entity)
    {
        $form = $this->createForm(new marksType(), $entity, array(
            'action' => $this->generateUrl('marks_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new marks entity.
     *
     * @Route("/new", name="marks_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new marks();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a marks entity.
     *
     * @Route("/{id}", name="marks_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PagomioPaymentBundle:marks')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find marks entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing marks entity.
     *
     * @Route("/{id}/edit", name="marks_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PagomioPaymentBundle:marks')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find marks entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a marks entity.
    *
    * @param marks $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(marks $entity)
    {
        $form = $this->createForm(new marksType(), $entity, array(
            'action' => $this->generateUrl('marks_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing marks entity.
     *
     * @Route("/{id}", name="marks_update")
     * @Method("PUT")
     * @Template("PagomioPaymentBundle:marks:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PagomioPaymentBundle:marks')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find marks entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('marks_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to allocate payment methods.
     *
     * @Route("/{id}/allocate", name="marks_allocate")
     * @Method("GET")
     * @Template()
     */
    public function allocateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PagomioPaymentBundle:marks')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find marks entity.');
        }

        $paymentMethods = $em->getRepository('PagomioPaymentBundle:payment_methods')->findAll($id);

        $connection = $em->getConnection();
        $sql = "SELECT * FROM payment_methods_has_marks WHERE marks_id = ".$id;
        $statement = $connection->prepare($sql);
        $statement->execute();

        $selected = $statement->fetchAll();

        //print_r($selected); die;

        return array(
            'entity' => $entity,
            'paymentMethods' => $paymentMethods,
            'selected' => $selected
        );
    }

    /**
     * Displays a form to allocate payment methods.
     *
     * @Route("/{id}/savemethod", name="save_method")
     * @Method("POST")
     * @Template()
     * @Template("PagomioPaymentBundle:marks:edit.html.twig")
     */
    public function saveMethodAction(Request $request, $id)
    {
        $method = $request->request->get('method_id');
        $commission = $request->request->get('commission');

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $sql = "delete from payment_methods_has_marks where marks_id = ".$id;
        $statement = $connection->prepare($sql);
        $statement->execute();

        if(!empty($method)){
            foreach($method as $key => $value){
                $entity = new payment_methods_has_marks();
                $entity->setPaymentMethodId($value);
                $entity->setMarksId($id);
                $entity->setCommission($commission[$key]);
                $em->persist($entity);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('marks_show', array(
             'id' => $id)));
    }


    /**
     * Deletes a marks entity.
     *
     * @Route("/{id}", name="marks_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PagomioPaymentBundle:marks')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find marks entity.');
            }

            $sql = "delete from payment_methods_has_marks where marks_id = ".$id;
            $connection = $em->getConnection();
            $statement = $connection->prepare($sql);
            $statement->execute();

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('marks'));
    }

    /**
     * Creates a form to delete a marks entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('marks_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
