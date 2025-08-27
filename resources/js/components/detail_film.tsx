import { Prologue } from '@/types';

interface Props {
    prologue: Prologue;
}
export default function DetailFilm({ prologue }: Props) {
    return (
        <div>
            <p className="whitespace-pre-line">{prologue.opening_crawl}</p>
        </div>
    );
}
