import { HydraAdmin, ResourceGuesser, CreateGuesser, EditGuesser } from '@api-platform/admin';
import {
    List,
    Datagrid,
    TextField,
    DateField,
    EditButton,
    BooleanField,
    ReferenceField,
    Pagination,
    TextInput,
    DateInput,
    BooleanInput,
    ReferenceInput,
    SelectInput,
    ImageInput,
    ImageField,
    FunctionField,
    required
} from 'react-admin';
import { RichTextInput } from 'ra-input-rich-text';
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

// Валидация при редактировании
const validateField = (value) => {
    return value ? undefined : 'Это поле обязательно для заполнения';
};

// LIST
export const ArticlesList = (props) => (
    <List {...props} pagination={<PostPagination />} filters={ArticlesFilters} >
        <Datagrid>
            <TextField source="title" label="Название" />
            <TextField source="shortContent" label="Текст" sortable={false} />
            <ImageField source="imageUrl" label="Изображение" sortable={false} />
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

export const TokensList = (props) => (
    <List {...props} pagination={<PostPagination />} actions={null}>
        <Datagrid>
            <FunctionField label="Токен" source="token" render={record => `Bearer ${record.token}`} />
        </Datagrid>
    </List>
);

// SHOW
export const TokenShow = (props) => (
    <List {...props} pagination={<PostPagination />} actions={null}>
        <Datagrid>
            <FunctionField label="Токен" source="token" render={record => `Bearer ${record.token}`} />
        </Datagrid>
    </List>
);

// CREATE
export const ArticlesCreate = (props) => (
    <CreateGuesser {...props}>
        <TextInput label="Название" source="title" name="title" />
        <RichTextInput label="Текст" source="content" name="content" />
        <ImageInput
            source="imageFile"
            label="Изображение"
            accept={{ 'image/*': ['.png', '.jpg'] }}
            placeholder={<p>Прикрепите изображение для статьи (jpg, png)</p>}
            validate={required()}
        >
            <ImageField source="src" title="title" />
        </ImageInput>
        <BooleanInput label="Опубликовано" source="isPublished" name="isPublished" />
        <ReferenceInput source="category" reference="categories" name="category">
            <SelectInput label="Категория" optionText="title" />
        </ReferenceInput>
    </CreateGuesser>
);

export const CategoriesCreate = (props) => (
    <CreateGuesser {...props}>
        <TextInput label="Название" source="title" name="title" />
    </CreateGuesser>
);

// EDIT
export const ArticlesEdit = (props) => (
    <EditGuesser {...props}>
        <TextInput label="Название" source="title" name="title" defaultValue="TEST" validate={validateField} />
        <RichTextInput label="Текст" source="content" name="content" validate={validateField} />
        <BooleanInput label="Опубилковано" source="isPublished" name="isPublished" />
        <ReferenceInput source="category" reference="categories" name="category" >
            <SelectInput label="Категория" optionText="title" validate={validateField} />
        </ReferenceInput>
    </EditGuesser>
);

export const CategoriesEdit = (props) => (
    <EditGuesser {...props}>
        <TextInput label="Название" source="title" name="title" validate={validateField} />
    </EditGuesser>
);

// Show result
export default (props) => (
    <HydraAdmin entrypoint={props.entrypoint}>
        <ResourceGuesser name="articles" list={ArticlesList} create={ArticlesCreate} edit={ArticlesEdit} options={{ label: 'Статьи' }} />
        <ResourceGuesser name="categories" list={CategoriesList} create={CategoriesCreate} edit={CategoriesEdit} options={{ label: 'Категории' }} />
        <ResourceGuesser name="api_tokens" list={TokensList} show={TokenShow} options={{ label: 'Токен' }} />
    </HydraAdmin>
);