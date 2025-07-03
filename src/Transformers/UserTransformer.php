<?php


namespace Cjj\RestfulResponse\Transformers;

use Cjj\RestfulResponse\Services\BaseDataTransformer;

class UserTransformer extends BaseDataTransformer
{
    public function transform(mixed $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString(),
        ];
    }

    public function transformWithProfile(mixed $user): array
    {
        $basic = $this->transform($user);

        return array_merge($basic, [
            'profile' => [
                'avatar' => $user->profile->avatar ?? null,
                'bio' => $user->profile->bio ?? null,
            ]
        ]);
    }
}
