import { Form, Head, Link, setLayoutProps } from '@inertiajs/react';
import Heading from '@/components/heading';
import ProposalFormFields from '@/components/proposal-form-fields';
import { Button } from '@/components/ui/button';
import ProposalController from '@/wayfinder/App/Http/Controllers/ProposalController';
import type { Inertia } from '@/wayfinder/types';

export default function ProposalsEdit({
    proposal,
}: Inertia.Pages.Proposals.Edit) {
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
            {
                title: '編集',
                href: ProposalController.edit(proposal),
            },
        ],
    });

    return (
        <>
            <Head title="プロポーザルを編集" />

            <div className="flex max-w-2xl flex-col gap-6 p-4">
                <Heading title="プロポーザルを編集" />

                <Form
                    {...ProposalController.update.form(proposal)}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <ProposalFormFields
                                proposal={proposal}
                                errors={errors}
                            />

                            <div className="flex items-center gap-2">
                                <Button disabled={processing}>保存する</Button>
                                <Button variant="ghost" asChild>
                                    <Link
                                        href={ProposalController.show(proposal)}
                                    >
                                        キャンセル
                                    </Link>
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}
