<script setup lang="ts">
import type {
    ColumnDef,
    ColumnFiltersState,
    SortingState,
    VisibilityState,
} from '@tanstack/vue-table';
import { valueUpdater } from '@/lib/utils'
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import {
    Table as TableComponent,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ArrowUpDown, ChevronDown } from 'lucide-vue-next';
import { h, ref } from 'vue';
import {Head, router} from '@inertiajs/vue3'; // Импортируем router для пагинации

import { ProjectSetting } from '@/types/ProjectSetting';
import { LengthAwarePaginator } from '@/types/LengthAwarePaginator';
import AppLayout from "@/layouts/AppLayout.vue";

// Пропсы
interface Props {
    projectSettings: LengthAwarePaginator;
}

const props = defineProps<Props>();

// Доступ к данным текущей страницы
const currentPageData = props.projectSettings.data;

// Колонки таблицы
const columns: ColumnDef<ProjectSetting>[] = [
    {
        accessorKey: 'id',
        header: '#',
        cell: ({ row }) => h('div', row.getValue('id')),
    },
    {
        accessorKey: 'name',
        header: 'Name',
        cell: ({ row }) => h('div', row.getValue('name')),
    },
    {
        accessorKey: 'value',
        header: 'Value',
        cell: ({ row }) => h('div', row.getValue('value')),
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const setting = row.original;

            return h('div', { class: 'flex gap-2' }, [
                h(Button, { variant: 'outline', size: 'sm' }, 'Edit'),
                h(Button, { variant: 'destructive', size: 'sm' }, 'Delete'),
            ]);
        },
    },
];

// Состояния таблицы
const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});

// Инициализация таблицы
const table = useVueTable({
    data: currentPageData, // Используем данные текущей страницы
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
    onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
    onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
    state: {
        get sorting() { return sorting.value; },
        get columnFilters() { return columnFilters.value; },
        get columnVisibility() { return columnVisibility.value; },
        get rowSelection() { return rowSelection.value; },
    },
});

// Функция для перехода на страницу
function goToPage(page: number) {
    router.visit(`/project-settings?page=${page}`);
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <div class="w-full">
            <div class="flex items-center py-4">
                <Input
                    class="max-w-sm"
                    placeholder="Filter names..."
                    :model-value="table.getColumn('name')?.getFilterValue() as string"
                    @update:model-value="table.getColumn('name')?.setFilterValue($event)"
                />
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" class="ml-auto">
                            Columns <ChevronDown class="ml-2 h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuCheckboxItem
                            v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
                            :key="column.id"
                            class="capitalize"
                            :model-value="column.getIsVisible()"
                            @update:model-value="(value) => {
                                column.toggleVisibility(!!value)
                            }"
                        >
                            {{ column.id }}
                        </DropdownMenuCheckboxItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
            <div class="rounded-md border">
                <TableComponent>
                    <TableHeader>
                        <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                            <TableHead v-for="header in headerGroup.headers" :key="header.id">
                                <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="table.getRowModel().rows?.length">
                            <TableRow
                                v-for="row in table.getRowModel().rows"
                                :key="row.id"
                                :data-state="row.getIsSelected() && 'selected'"
                            >
                                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </TableCell>
                            </TableRow>
                        </template>
                        <TableRow v-else>
                            <TableCell
                                :colspan="columns.length"
                                class="h-24 text-center"
                            >
                                No results.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </TableComponent>
            </div>

            <!-- Пагинация -->
            <div class="flex items-center justify-start space-x-2 py-4">
<!--                <div class="flex-1 text-sm text-muted-foreground">-->
<!--                    {{ table.getFilteredSelectedRowModel().rows.length }} of-->
<!--                    {{ table.getFilteredRowModel().rows.length }} row(s) selected.-->
<!--                </div>-->
                <div class="space-x-2">
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="!props.projectSettings.prev_page_url"
                        @click="goToPage(props.projectSettings.current_page - 1)"
                    >
                        Previous
                    </Button>
                    <span>Page {{ props.projectSettings.current_page }} of {{ props.projectSettings.last_page }}</span>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="!props.projectSettings.next_page_url"
                        @click="goToPage(props.projectSettings.current_page + 1)"
                    >
                        Next
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
