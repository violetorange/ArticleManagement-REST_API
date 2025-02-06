import React, { useState, useEffect } from 'react';
import { TextField, Button, Box, Typography, Grid, FormControl, CircularProgress } from '@mui/material';

export default function LoginPage({ user }) {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [isAuthenticated, setIsAuthenticated] = useState(false);

    const handleSubmit = async (e) => {
        setLoading(true);
        setError(null);

        const response = await fetch('/authorization', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        });

        setLoading(false);

        if (!response.ok) {
            const data = await response.json();
            setError(data.error);

            return;
        }

        setIsAuthenticated(true);
    };

    if (loading) {
        return <CircularProgress />;
    }

    if (error) {
        return <div>{ error }</div>
    }

    if (isAuthenticated || user) {
        return (
            <Box
                sx={{
                    display: 'flex',
                    flexDirection: 'column',
                    alignItems: 'center',
                    justifyContent: 'center',
                    height: '100vh',
                }}
            >
                <Typography variant="h4" gutterBottom>
                    Добро пожаловать!
                </Typography>
                <Button
                    href='/admin'
                    variant="contained"
                    color="primary"
                >
                    Admin
                </Button>
                <br/>
                <Button
                    href='/logout'
                    variant="contained"
                    color="primary"
                >
                    Выйти
                </Button>
            </Box>
        );
    }

    return (
        <Box
            sx={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                justifyContent: 'center',
                height: '100vh',
            }}
        >
            <Typography variant="h4" gutterBottom>
                ADMIN
            </Typography>
            <form onSubmit={handleSubmit}>
                <Grid container spacing={2} direction="column">
                    <Grid item>
                        <FormControl fullWidth>
                            <TextField
                                label="E-mail"
                                type="email"
                                variant="outlined"
                                onChange={(e) => setEmail(e.target.value)}
                                required
                            />
                        </FormControl>
                    </Grid>
                    <Grid item>
                        <FormControl fullWidth>
                            <TextField
                                label="Пароль"
                                type="password"
                                variant="outlined"
                                onChange={(e) => setPassword(e.target.value)}
                                required
                            />
                        </FormControl>
                    </Grid>
                    <Grid item>
                        <Button
                            type="submit"
                            variant="contained"
                            color="primary"
                            fullWidth
                        >
                            Войти
                        </Button>
                    </Grid>
                </Grid>
            </form>
        </Box>
    );
}