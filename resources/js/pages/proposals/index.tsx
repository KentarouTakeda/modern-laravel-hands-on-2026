import { Head, Link } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import ProposalController from '@/wayfinder/App/Http/Controllers/ProposalController';
import { ProposalTypeMeta } from '@/wayfinder/App/Enums/ProposalType';
import type { Inertia } from '@/wayfinder/types';

export default function ProposalsIndex({
    proposals,
}: Inertia.Pages.Proposals.Index) {
    return (
        <>
            <Head title="プロポーザル" />

            <div className="flex flex-col gap-6 p-4">
                <div className="flex items-start justify-between gap-4">
                    <Heading
                        title="プロポーザル"
                        description="あなたが提出したプロポーザルの一覧です"
                    />
                    <Button asChild>
                        <Link href={ProposalController.create()}>
                            <Plus />
                            新規作成
                        </Link>
                    </Button>
                </div>

                {proposals.length === 0 ? (
                    <p className="text-sm text-muted-foreground">
                        まだプロポーザルがありません。
                    </p>
                ) : (
                    <ul className="divide-y rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        {proposals.map((proposal) => (
                            <li key={proposal.id}>
                                <Link
                                    href={ProposalController.show(proposal)}
                                    className="flex flex-col gap-1 p-4 hover:bg-accent/50"
                                    prefetch
                                >
                                    <div className="flex items-center gap-2">
                                        <Badge variant="secondary">
                                            {
                                                ProposalTypeMeta[proposal.type]
                                                    .label
                                            }
                                        </Badge>
                                        <span className="font-medium">
                                            {proposal.title}
                                        </span>
                                    </div>
                                    <p className="line-clamp-2 text-sm text-muted-foreground">
                                        {proposal.abstract}
                                    </p>
                                </Link>
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </>
    );
}

ProposalsIndex.layout = {
    breadcrumbs: [
        {
            title: 'プロポーザル',
            href: ProposalController.index(),
        },
    ],
};
