import { HydraAdmin, ResourceGuesser } from '@api-platform/admin';
import { List, Datagrid, TextField, DateField, EditButton, BooleanField, Pagination, TextInput, DateInput, BooleanInput  } from 'react-admin';

import React from 'react';

// Pagination
const PostPagination = () => <Pagination rowsPerPageOptions={[5, 10, 25, 50]} />;

// Filters
const ArticleFilters = [
    <TextInput label="Название" source="title" name="title" />,
    <TextInput label="Текст" source="content" name="content" />,
    <DateInput label="Создано с" source="createdAt.after" name="createdAt.after" />,
    <DateInput label="Создано до" source="createdAt.before" name="createdAt.before" />,
    <DateInput label="Изменено с" source="changedAt.after" name="changedAt.after" />,
    <DateInput label="Изменено до" source="changedAt.before" name="changedAt.before" />,
    <TextInput label="Категория" source="category.title" name="category.title" />,
    <BooleanInput label="Опубилковано" source="isPublished" name="isPublished" defaultValue="true" />
];

const CategoryFilters = [
    <TextInput label="Название" source="title" name="title" />
];

// LIST
export const ArticlesList = (props) => (
    <List {...props} pagination={<PostPagination />} filters={ArticleFilters} >
        <Datagrid>
            <TextField source="title" label="Название" />
            <TextField source="shortContent" label="Текст" sortable={false} />
            <TextField source="image" label="Изображение" sortable={false} />
            <DateField source="createdAt" label="Создано" />
            <DateField source="changedAt" label="Изменено" />
            <TextField label="Категория" source="category.title" name="category.title" />
            <BooleanField  source="isPublished" label="Опубликовано" />
            <EditButton />
        </Datagrid>
    </List>
);

export const CategoriesList = (props) => (
    <List {...props} pagination={<PostPagination />} filters={CategoryFilters}>
        <Datagrid>
            <TextField source="title" label="Название" />
            <EditButton />
        </Datagrid>
    </List>
);

export default (props) => (
    <HydraAdmin entrypoint={props.entrypoint}>
        <ResourceGuesser name="articles" list={ArticlesList} />
        <ResourceGuesser name="categories" list={CategoriesList} />
    </HydraAdmin>
);