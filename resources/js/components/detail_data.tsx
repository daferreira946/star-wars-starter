import { Details, Info, Prologue } from '@/types';
import DetailFilm from '@/components/detail_film';
import DetailPeople from '@/components/detail_people';

interface Props {
    info: Info,
    type: string,
    title: string,
}

export default function DetailData({ info, type, title}: Props) {
    return (
        <div>
            <div className="border-b-1 border-pinkish-gray py-3">
                <h3 className="text-md text-xl font-bold text-gray-800">{title}</h3>
            </div>
            {type === 'films' ? <DetailFilm prologue={info as Prologue} /> : <DetailPeople details={info as Details} />}
        </div>
    );
}
