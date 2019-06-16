<?php

namespace Squadron\CRUD\Policies\Contracts;

interface CRUDPolicyContract
{
    public function getList($currentUser): bool;

    public function getSingle($currentUser, $user): bool;

    public function create($currentUser): bool;

    public function update($currentUser, $user): bool;

    public function delete($currentUser, $user): bool;
}
