# AnimeMovil - Plataforma de Streaming de Anime

Una aplicación web PHP para streaming de anime con integración a Crunchyroll.

## 🚀 Instalación Rápida

### Opción 1: Con XAMPP (Recomendado)

1. **Instala XAMPP** desde [https://www.apachefriends.org/](https://www.apachefriends.org/)

2. **Ejecuta como Administrador**: `instalar-completo.bat`

3. **Configura la base de datos**:
   - Ve a http://localhost/phpmyadmin
   - Crea base de datos `animemovil`
   - Importa `animemovil.sql`

4. **Visita tu sitio**: http://localhost/AnimeMovil/

### Opción 2: Servidor Local

1. **Instala PHP** (o usa XAMPP solo para MySQL)

2. **Ejecuta**: `servidor-local.bat`

3. **Configura la base de datos** con: `configurar-bd.bat`

4. **Visita tu sitio**: http://localhost:8000

## 📁 Estructura del Proyecto

```
AnimeMovil/
├── api/              # API endpoints
├── assets/           # Recursos estáticos
├── config/           # Configuraciones
├── pagues/           # Páginas principales
├── stream/           # Sistema de streaming
└── animemovil.sql    # Base de datos
```

## 🛠️ Scripts Disponibles

- `instalar-completo.bat` - Instalación completa con XAMPP
- `servidor-local.bat` - Servidor PHP local
- `configurar-bd.bat` - Solo configurar base de datos

## 🌐 URLs Disponibles

- **Inicio**: `/`
- **Buscar anime**: `/anime`
- **Favoritos**: `/misAnimes`
- **Login**: `/entrar`
- **Registro**: `/registrar`
- **Panel**: `/panel`

## ⚙️ Requisitos

- PHP 7.0+
- MySQL/MariaDB
- Apache (opcional con XAMPP)
- Extensión cURL de PHP

## 🐛 Solución de Problemas

Si tienes errores:

1. Verifica que MySQL esté ejecutándose
2. Confirma que la base de datos `animemovil` existe
3. Revisa que `animemovil.sql` esté importado
4. Asegúrate de que las rutas en `config.php` sean correctas

---

**Desarrollado por**: Hector Duarte  
**Año**: 2017-2025
