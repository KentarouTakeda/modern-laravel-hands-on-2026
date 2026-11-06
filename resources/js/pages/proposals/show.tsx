import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import { Pencil, Trash2 } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import ProposalController from '@/wayfinder/App/Http/Controllers/ProposalController';
import { ProposalTypeMeta } from '@/wayfinder/App/Enums/ProposalType';
import type { Inertia } from '@/wayfinder/types';

export default function ProposalsShow({
    proposal,
}: Inertia.Pages.Proposals.Show) {
    setLayoutProps({
        breadcrumbs: [
            {
                title: 'プロポーザル',
                href: ProposalController.index(),
            },
            {
                title: proposal.title,
                href: ProposalController.show(proposal),
            },
        ],
    });

    return (
        <>
            <Head title={proposal.title} />

            <div className="flex max-w-2xl flex-col gap-6 p-4">
                <div className="space-y-2">
                    <Badge variant="secondary">
                        {ProposalTypeMeta[proposal.type].label}
                    </Badge>
                    <h1 className="text-xl font-semibold tracking-tight">
                        {proposal.title}
                    </h1>
                </div>

                <p className="text-sm leading-relaxed whitespace-pre-wrap">
                    {proposal.abstract}
                </p>

                <div className="flex items-center gap-2">
                    <Button variant="outline" asChild>
                        <Link href={ProposalController.edit(proposal)}>
                            <Pencil />
                            編集
                        </Link>
                    </Button>

                    <Dialog>
                        <DialogTrigger asChild>
                            <Button variant="destructive">
                                <Trash2 />
                                削除
                            </Button>
                        </DialogTrigger>
                        <DialogContent>
                            <DialogTitle>
                                このプロポーザルを削除しますか？
                            </DialogTitle>
                            <DialogDescription>
                                削除したプロポーザルは元に戻せません。
                            </DialogDescription>
                            <Form
                                {...ProposalController.destroy.form(proposal)}
                            >
                                {({ processing }) => (
                                    <DialogFooter className="gap-2">
                                        <DialogClose asChild>
                                            <Button variant="secondary">
                                                キャンセル
                                            </Button>
                                        </DialogClose>
                                        <Button
                                            variant="destructive"
                                            disabled={processing}
                                        >
                                            削除する
                                        </Button>
                                    </DialogFooter>
                                )}
                            </Form>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>
        </>
    );
}
