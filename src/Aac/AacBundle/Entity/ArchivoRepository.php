<?php

namespace Aac\AacBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArchivoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArchivoRepository extends EntityRepository
{
    public function findOneByIdJoinedToUsuarios($id)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT a, u FROM AacAacBundle:Archivo a
                JOIN a.para u
                WHERE a.para = :id'
            )->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    
    public function findBuscarPorUsuario($idUser)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder('a')
                    ->where('a.de = :de OR a.para = :de OR a.para = :para99')
                    ->setParameter('de',  $idUser)
                    //->setParameter('de1', $idUser)
                    ->setParameter('para99', 99999)
                    ->getQuery();
        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AacAacBundle:Archivo p ORDER BY p.descripcion ASC'
            )
            ->getResult();
    }    
}