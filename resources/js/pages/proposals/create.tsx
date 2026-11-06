import { Form, Head } from '@inertiajs/react';
import Heading from '@/components/heading';
import ProposalFormFields from '@/components/proposal-form-fields';
import { Button } from '@/components/ui/button';
import ProposalController from '@/wayfinder/App/Http/Controllers/ProposalController';

export default function ProposalsCreate() {
    return (
        <>
            <Head title="プロポーザルを作成" />

            <div className="flex max-w-2xl flex-col gap-6 p-4">
                <Heading title="プロポーザルを作成" />

                <Form
                    {...ProposalController.store.form()}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <ProposalFormFields errors={errors} />

                            <Button disabled={processing}>作成する</Button>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

ProposalsCreate.layout = {
    breadcrumbs: [
        {
            title: 'プロポーザル',
            href: ProposalController.index(),
        },
        {
            title: '新規作成',
            href: ProposalController.create(),
        },
    ],
};
