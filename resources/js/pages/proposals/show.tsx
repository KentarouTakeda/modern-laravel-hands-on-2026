import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import { Pencil, Sparkles, Trash2 } from 'lucide-react';
import Heading from '@/components/heading';
import ProposalReviewCard from '@/components/proposal-review-card';
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
import { Spinner } from '@/components/ui/spinner';
import ProposalController from '@/wayfinder/App/Http/Controllers/ProposalController';
import ProposalReviewController from '@/wayfinder/App/Http/Controllers/ProposalReviewController';
import { ProposalTypeMeta } from '@/wayfinder/App/Enums/ProposalType';
import type { Inertia } from '@/wayfinder/types';

export default function ProposalsShow({
    proposal,
    reviews,
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

                <section className="space-y-4 border-t pt-6">
                    <div className="flex items-start justify-between gap-4">
                        <Heading
                            variant="small"
                            title="AI講評"
                            description="AIがプロポーザルを講評し、結果を保存します"
                        />
                        <Form
                            {...ProposalReviewController.store.form(proposal)}
                            options={{ preserveScroll: true }}
                        >
                            {({ processing }) => (
                                <Button variant="outline" disabled={processing}>
                                    {processing ? <Spinner /> : <Sparkles />}
                                    {processing ? '講評中…' : '講評してもらう'}
                                </Button>
                            )}
                        </Form>
                    </div>

                    {reviews.length === 0 ? (
                        <p className="text-sm text-muted-foreground">
                            まだ講評はありません。
                        </p>
                    ) : (
                        reviews.map((review) => (
                            <ProposalReviewCard
                                key={review.id}
                                review={review}
                            />
                        ))
                    )}
                </section>
            </div>
        </>
    );
}
