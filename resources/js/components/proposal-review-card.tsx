import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import type { App } from '@/wayfinder/types';

export default function ProposalReviewCard({
    review,
}: {
    review: App.Models.ProposalReview;
}) {
    return (
        <Card>
            <CardHeader>
                <CardTitle className="flex items-baseline gap-1">
                    <span className="text-2xl">{review.score}</span>
                    <span className="text-sm text-muted-foreground">/ 10</span>
                </CardTitle>
                {review.created_at && (
                    <CardDescription>
                        {new Date(review.created_at).toLocaleString('ja-JP')}
                    </CardDescription>
                )}
            </CardHeader>
            <CardContent className="space-y-4 text-sm">
                <p className="leading-relaxed whitespace-pre-wrap">
                    {review.summary}
                </p>

                <div className="space-y-1">
                    <h3 className="font-medium">良い点</h3>
                    <ul className="list-disc space-y-1 pl-5">
                        {review.strengths.map((strength, index) => (
                            <li key={index}>{strength}</li>
                        ))}
                    </ul>
                </div>

                <div className="space-y-1">
                    <h3 className="font-medium">改善点</h3>
                    <ul className="list-disc space-y-1 pl-5">
                        {review.improvements.map((improvement, index) => (
                            <li key={index}>{improvement}</li>
                        ))}
                    </ul>
                </div>
            </CardContent>
        </Card>
    );
}
