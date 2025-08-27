import { Link } from '@inertiajs/react';

interface MainLayoutProps {
    children: React.ReactNode;
}
export default function MainLayout({children}: MainLayoutProps) {
    return (
        <div className="min-h-screen bg-gray-200">
            <header className="bg-white shadow-sm">
                <nav className="mx-auto py-6">
                    <div className="text-center">
                        <Link href="/">
                            <h1 className="text-2xl font-bold text-greenteal cursor-pointer">SWStarter</h1>
                        </Link>
                    </div>
                </nav>
            </header>

            <main className="container mx-auto px-4 py-8">
                {children}
            </main>
        </div>
    );
}
