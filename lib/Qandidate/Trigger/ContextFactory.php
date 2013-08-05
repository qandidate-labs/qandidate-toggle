<?php

namespace Qandidate\Trigger;

/**
 * Context factory is implemented in an application to provide the context for 
 * feature flipping.
 *
 * To abstract away the business object from the feature library, the 
 * application is responsible for mapping the business objects into a context 
 * based for feature flipping. For example:
 *
 *     $request = ...;
 *     $user    = $repository->findBy(..);
 *     $context = new Context();
 *     $context->set('user_id', $user->getId());
 *     $context->set('company_id', $user->getCompanyId());
 *     $context->set('ip', $request->getClientIp());
 */
abstract class ContextFactory
{
    /**
     * @return Context
     */
    abstract public function createContext();
}
