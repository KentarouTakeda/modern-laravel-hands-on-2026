<?php

namespace Database\Seeders;

use App\Enums\ProposalType;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Proposal::factory()
            ->for($user)
            ->forEachSequence(
                [
                    'title' => 'Laravel 13 で始めるモダンな Web アプリケーション開発',
                    'type' => ProposalType::Talk,
                    'abstract' => "Laravel 13 と Inertia v3 を組み合わせ、型安全なフルスタック開発を実現する方法を紹介します。\n\nWayfinder による TypeScript 型の自動生成や、実プロジェクトでの運用ノウハウもお話しします。",
                ],
                [
                    'title' => '5分でわかる Wayfinder',
                    'type' => ProposalType::LightningTalk,
                    'abstract' => 'PHP のコードから TypeScript のルート関数や型を生成する Wayfinder の魅力を 5 分で駆け抜けます。',
                ],
                [
                    'title' => 'ハンズオン: Inertia + React で CRUD アプリを作ろう',
                    'type' => ProposalType::Workshop,
                    'abstract' => "参加者と一緒に、ゼロから CRUD アプリケーションを作り上げるワークショップです。\n\nノート PC と PHP 8.5 / Node.js の環境をご用意ください。",
                ],
            )
            ->create();

        Proposal::factory()->count(3)->create();
    }
}
