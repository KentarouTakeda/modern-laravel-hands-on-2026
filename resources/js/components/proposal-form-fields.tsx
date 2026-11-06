import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import {
    ProposalType,
    ProposalTypeMeta,
} from '@/wayfinder/App/Enums/ProposalType';
import type { App } from '@/wayfinder/types';

type Props = {
    proposal?: App.Models.Proposal;
    errors: Partial<
        Record<
            keyof App.Http.Controllers.ProposalController.Store.Request,
            string
        >
    >;
};

export default function ProposalFormFields({ proposal, errors }: Props) {
    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="title">タイトル</Label>
                <Input
                    id="title"
                    name="title"
                    defaultValue={proposal?.title}
                    required
                    maxLength={255}
                    placeholder="発表タイトル"
                />
                <InputError message={errors.title} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="type">種別</Label>
                <Select
                    name="type"
                    defaultValue={proposal?.type ?? ProposalType.Talk}
                >
                    <SelectTrigger id="type" className="w-full">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        {Object.values(ProposalType).map((type) => (
                            <SelectItem key={type} value={type}>
                                {ProposalTypeMeta[type].label}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
                <InputError message={errors.type} />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="abstract">概要</Label>
                <Textarea
                    id="abstract"
                    name="abstract"
                    defaultValue={proposal?.abstract}
                    required
                    maxLength={5000}
                    rows={8}
                    placeholder="発表の概要"
                />
                <InputError message={errors.abstract} />
            </div>
        </>
    );
}
