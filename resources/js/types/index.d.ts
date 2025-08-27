import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export type TypeSearchInterface = 'people' | 'films';

interface ItemInterface {
    name: string | null;
    title: string | null;
    id: number;
}

interface Reference {
    url: string;
    name: string | null;
    title: string | null;
}


interface Details {
    birth_year: string;
    gender: string;
    eye_color: string;
    hair_color: string;
    height: string;
    mass: string;
}

interface Prologue {
    opening_crawl: string;
}

type Info = Details | Prologue;


interface Data {
    id: number;
    name: string | null;
    title: string | null;
    type: TypeSearchInterface;
    references: Reference[];
    info: Info;
}
