import { HydraAdmin, ResourceGuesser, CreateGuesser, EditGuesser } from '@api-platform/admin';
import {
    List,
    Datagrid,
    TextField,
    DateField,
    EditButton,
    BooleanField,
    ReferenceField,
    ReferenceOneField,
    Pagination,
    TextInput,
    DateInput,
    BooleanInput,
    ReferenceInput
} from 'react-admin';

import React from 'react';

// Pagination
const PostPagination = () => <Pagination rowsPerPageOptions={[5, 10, 25, 50]} />;

// Filters
const ArticlesFilters = [
    <TextInput label="Название" source="title" name="title" />,
    <TextInput label="Текст" source="content" name="content" />,
    <DateInput label="Создано с" source="createdAt.after" name="createdAt.after" />,
    <DateInput label="Создано до" source="createdAt.before" name="createdAt.before" />,
    <DateInput label="Изменено с" source="changedAt.after" name="changedAt.after" />,
    <DateInput label="Изменено до" source="changedAt.before" name="changedAt.before" />,
    <TextInput label="Категория" source="category.title" name="category.title" />,
    <BooleanInput label="Опубилковано" source="isPublished" name="isPublished" defaultValue="true" />
];

const CategoriesFilters = [
    <TextInput label="Название" source="title" name="title" />
];

// LIST
export const ArticlesList = (props) => (
    <List {...props} pagination={<PostPagination />} filters={ArticlesFilters} >
        <Datagrid>
            <TextField source="title" label="Название" />
            <TextField source="shortContent" label="Текст" sortable={false} />
            <TextField source="image" label="Изображение" sortable={false} />
            <DateField source="createdAt" label="Создано" />
            <DateField source="changedAt" label="Изменено" />
            <ReferenceField source="category" reference="categories" label="Категория" sortBy="category.title">
                <TextField source="title" />
            </ReferenceField>
            <BooleanField  source="isPublished" label="Опубликовано" />
            <EditButton />
        </Datagrid>
    </List>
);

export const CategoriesList = (props) => (
    <List {...props} pagination={<PostPagination />} filters={CategoriesFilters}>
        <Datagrid>
            <TextField source="title" label="Название" />
            <EditButton />
        </Datagrid>
    </List>
);

// CREATE
export const ArticlesCreate = (props) => (
    <CreateGuesser {...props}>
        <TextInput label="Название" source="title" name="title" />
        <TextInput label="Текст" source="content" name="content" />
        <TextInput label="Изображение" source="image" name="image" />
        <BooleanInput label="Опубликовано" source="isPublished" name="isPublished" />
        <ReferenceInput source="category" reference="categories" name="category" />
    </CreateGuesser>
);

// EDIT
export const ArticlesEdit = (props) => (
    <EditGuesser {...props}>
        <TextInput label="Название" source="title" name="title" />
        <TextInput label="Текст" source="content" name="content" />
        <TextInput label="Изображение" source="image" name="image" />
        <BooleanInput label="Опубилковано" source="isPublished" name="isPublished" />
        <ReferenceInput source="category" reference="categories" name="category" />
    </EditGuesser>
);

export const CategoriesEdit = (props) => (
    <EditGuesser {...props}>
        <TextInput label="Название" source="title" name="title" />
    </EditGuesser>
);

export default (props) => (
    <HydraAdmin entrypoint={props.entrypoint}>
        <ResourceGuesser name="articles" list={ArticlesList} create={ArticlesCreate} edit={ArticlesEdit} />
        <ResourceGuesser name="categories" list={CategoriesList} edit={CategoriesEdit} />
    </HydraAdmin>
);