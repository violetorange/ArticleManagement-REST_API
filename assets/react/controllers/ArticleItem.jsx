import React, {useEffect, useRef, useState} from 'react';
import { Grid, Card, CardContent, CardMedia, Typography, Pagination } from '@mui/material';

export default function ArticleItem({ articleId }) {
    const [article, setArticle] = useState([]);
    const [loading, setIsLoading] = useState(false);
    const [error, setError] = useState();

    useEffect(() => {
        const fetchArticle = async () => {
            setIsLoading(true);

            try {
                const response = await fetch(`/api/articles/${articleId}.jsonld`);
                const data = await response.json();
                setArticle(data);
            } catch (error) {
                setError(error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchArticle();
    }, []);

    if (loading) {
        return <div>Загрузка...</div>;
    }

    if (error) {
        return <div>Упс! Что-то пошло не так</div>
    }

    return (
        <div>Статья: {article.title}</div>
    );
}