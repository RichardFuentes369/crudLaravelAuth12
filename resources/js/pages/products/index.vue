<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { create, edit } from '@/routes/products/index';
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert"
import { CheckCircle2Icon } from 'lucide-vue-next';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

/** 1. Interfaz de Datos **/
interface Product {
    id: number,
    name: string,
    price: number,
    description: string,
}

/** 2. Interfaz de Props: Objeto Paginado + perPage **/
interface Props {
    products: {
        data: Product[];
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
        current_page: number;
        last_page: number;
    };
    perPage: number; // Propiedad para el límite actual de paginación
}

// Get props from Inertia
const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: '/products',
    },
];

const page = usePage()

/** 3. Funciones de Lógica **/

// Maneja la eliminación de productos
const handleDelete = (productId: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        router.delete(route('products.delete', productId), {
            preserveScroll: true
        }); 
    }
};

// Maneja el cambio del límite de ítems por página
const updatePerPage = (event: Event) => {
    const target = event.target as HTMLSelectElement;
    const newPerPage = target.value;
    
    // Recarga la página enviando el nuevo parámetro 'per_page'
    router.get(
        route('products.index'),
        { per_page: newPerPage },
        { 
            preserveState: true, // Mantiene los filtros o estado actual de la página
            preserveScroll: true
        }
    );
};
</script>

<template>
    <Head title="Products Index" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div v-if="page.props.flash.message" class="mb-4">
                <Alert variant="default" class="bg-blue-200">
                    <CheckCircle2Icon class="h-4 w-4" />
                    <AlertTitle>Éxito</AlertTitle>
                    <AlertDescription>
                        {{ page.props.flash.message }}
                    </AlertDescription>
                </Alert>
            </div>
            
            <div class="flex justify-between items-center mb-4">
                <Link :href="create()">
                    <Button>Crear producto</Button>
                </Link>

                <div class="flex items-center space-x-2">
                    <label for="perPageSelect" class="text-sm font-medium text-gray-700">Items por página:</label>
                    <select 
                        id="perPageSelect"
                        :value="props.perPage"
                        @change="updatePerPage"
                        class="border rounded-md p-1 text-sm focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <Table>
                <TableCaption>
                    Mostrando {{ props.products.data.length }} de {{ props.products.total }} registros. 
                    (Página {{ props.products.current_page }} de {{ props.products.last_page }})
                </TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]">Id</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Price</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead class="text-center">Action</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="product in props.products.data" :key="product.id">
                        <TableCell>{{product.id}}</TableCell>
                        <TableCell class="font-medium">{{product.name}}</TableCell>
                        <TableCell>{{product.price}}</TableCell>
                        <TableCell>{{product.description}}</TableCell>
                        <TableCell class="text-center space-x-2">
                            <Link :href="edit({ product: product.id })">
                                <Button class="bg-blue-600 hover:bg-blue-700">Edit</Button>
                            </Link>
                            <Button class="bg-red-600 hover:bg-red-700" @click="handleDelete(product.id)">Eliminar</Button>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            
            <div class="mt-4 flex justify-center space-x-2">
                <template v-for="(link, key) in props.products.links" :key="key">
                    <Link 
                        v-if="link.url"
                        :href="link.url"
                        v-html="link.label"
                        class="px-3 py-1 text-sm rounded-md border transition-colors duration-150"
                        :class="{ 
                            'bg-gray-700 text-white border-gray-700 font-bold': link.active,
                            'hover:bg-gray-100': !link.active
                        }"
                        preserve-scroll
                    />
                    <span 
                        v-else 
                        v-html="link.label"
                        class="px-3 py-1 text-sm rounded-md border text-gray-400 cursor-not-allowed"
                    ></span>
                </template>
            </div>
        </div>
    </AppLayout>
</template>