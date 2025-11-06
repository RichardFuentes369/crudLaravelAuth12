<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a product',
        href: '/products/create',
    },
];

const form = useForm({
    name: '',
    price: '',
    description: ''
})

const handleSubmit = () => {
    form.post(route('products.store'));
}

</script>

<template>
    <Head title="Create a Product" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <form @submit.prevent="handleSubmit" class="w-8/12 space-y-7">
                <div class="space-y-2">
                    <Label for="Nombre producto">Nombre</Label>
                    <Input v-model="form.name" type="text" placeholder="Ingrese el nombre"></Input>
                    <div class="text-sm text-red-000" v-if="form.errors.name">{{ form.errors.name }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Precio producto">Precio</Label>
                    <Input v-model="form.price" type="number" placeholder="Ingrese el precio"></Input>
                    <div class="text-sm text-red-000" v-if="form.errors.price">{{ form.errors.price }}</div>
                </div>
                <div class="space-y-2">
                    <Label for="Descripcion producto">Descripcion</Label>
                    <Input v-model="form.description" type="text" placeholder="Ingrese una descipcion"></Input>
                    <div class="text-sm text-red-000" v-if="form.errors.description">{{ form.errors.description }}</div>
                </div>
                <Button type="submit">Añadir producto</Button>
            </form>
        </div>
    </AppLayout>
</template>
