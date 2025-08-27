import { Reference } from '@/types';
import { Link } from '@inertiajs/react';

interface Props {
    title: string;
    references: Reference[];
}
export default function DetailReferences({ title, references }: Props) {
    return (
        <div>
            <h3 className="text-md w-full border-b-1 border-pinkish-gray py-3 text-xl font-bold text-gray-800">{title}</h3>
            {references.map((reference, index) => (
                <span key={index}>
                    <Link href={reference.url} className="text-emerald-link">
                        {reference.name ?? reference.title}
                    </Link>
                    {index < references.length - 1 && ', '}
                </span>
            ))}
        </div>
    );
}
