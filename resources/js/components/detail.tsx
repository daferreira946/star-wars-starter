import { Data } from '@/types';
import DetailData from '@/components/detail_data';
import DetailReferences from '@/components/detail_references';


const PagesInfo = {
    films: {
        title: 'Opening Crawl',
        references: 'Characters',
    },
    people: {
        title: 'Details',
        references: 'Movies',
    },
    default: {
        title: 'Default',
        references: 'Default',
    },
};

interface Props {
    data: Data
}

export default function Detail({data}: Props) {
    const pagesInfo = PagesInfo[data?.type || 'default'];
    return (
        <>
            {
                pagesInfo.title !== 'Default' ?
                    (
                        <>
                            <h2 className="mb-6 text-2xl font-bold text-gray-800">{data.name}</h2>

                            <div className="grid grid-cols-2 gap-50">
                                <DetailData info={data.info} type={data.type} title={pagesInfo.title} />
                                <DetailReferences title={pagesInfo.references} references={data.references} />
                            </div>
                        </>
                    ) : (
                        <h2 className="mb-6 text-2xl font-bold text-gray-800">{pagesInfo.title}</h2>
                    )
            }
        </>
    );
}
