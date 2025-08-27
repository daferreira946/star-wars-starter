import { Details } from '@/types';

interface Props {
    details: Details
}
export default function DetailPeople({ details }: Props) {
    return (
        <div>
            <ul>
                <li>Birth Year: {details.birth_year}</li>
                <li>Gender: {details.gender}</li>
                <li>Eye Color: {details.eye_color}</li>
                <li>Hair Color: {details.hair_color}</li>
                <li>Height: {details.height}</li>
                <li>Mass: {details.mass}</li>
            </ul>
        </div>
    );
}