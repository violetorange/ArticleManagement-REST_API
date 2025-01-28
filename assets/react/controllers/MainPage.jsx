import React, {useEffect, useRef, useState} from 'react';
import { Grid, Card, CardContent, CardMedia, Typography, Pagination } from '@mui/material';

export default function MainArticlesList() {
    const [articles, setArticles] = useState([]);
    const [loading, setIsLoading] = useState(false);
    const [error, setError] = useState();
    const [page, setPage] = useState(1);
    const [totalPages, setTotalPages] = useState(0);
    const itemsPerPage = 9;

    useEffect(() => {
        const fetchArticles = async () => {
            setIsLoading(true);

            try {
                const response = await fetch(`/api/articles.jsonld?isPublished=true&page=${page}&itemsPerPage=${itemsPerPage}`);
                const articles = await response.json();
                setArticles(articles.member);
                setTotalPages(Math.ceil(articles.totalItems / itemsPerPage));
            } catch (error) {
                setError(error);
            } finally {
                setIsLoading(false);
            }
        };

        fetchArticles();
    }, [page]);

    const handleChange = (event, value) => {
        setPage(value);
    };

    if (loading) {
        return <div>Загрузка...</div>;
    }

    if (error) {
        return <div>Упс! Что-то пошло не так</div>
    }
console.log(articles);
    return (
        <div>
            <Grid container justifyContent="center">
                <Grid container item xs={12} sm={8} md={6} spacing={3} justifyContent="center">
                    {articles.map((article) => (
                        <Grid item xs={12} sm={4} md={4} key={article["@id"]}>
                            <a href={`/articles/${article.id}`} style={{ textDecoration: 'none' }}>
                                <Card
                                    style={{ width: '300px' }}
                                    sx={{
                                        width: '300px',
                                        transition: 'transform 0.3s, box-shadow 0.3s',
                                        '&:hover': {
                                            transform: 'scale(1.05)', // Увеличение карточки при наведении
                                            boxShadow: '0 4px 20px rgba(0, 0, 0, 0.2)', // Добавление тени
                                        }
                                    }}
                                >
                                    <CardMedia
                                        component="img"
                                        height="200"
                                        image={article.imageUrl}
                                        alt={article.title}
                                    />
                                    <CardContent>
                                        <Typography gutterBottom variant="h6">
                                            {article.title}
                                        </Typography>
                                    </CardContent>
                                </Card>
                            </a>
                        </Grid>
                    ))}
                </Grid>
            </Grid>
            <Pagination
                count={totalPages}
                page={page}
                onChange={handleChange}
                variant="outlined"
                shape="rounded"
                sx={{ marginTop: 2, display: 'flex', justifyContent: 'center' }}
            />
        </div>
    );
}