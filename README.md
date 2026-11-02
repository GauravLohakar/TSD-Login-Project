# TSD Login Page — Render + Neon

This is the PHP login/registration project configured for Render and a **new Neon PostgreSQL database**.

## 1. Create a NEW Neon database

1. Sign in at https://neon.tech/ and choose **Create project**.
2. Give the project a new name (for example, `tsd-login-db`). Do not select or reuse an old project.
3. Open the new project and select **SQL Editor**.
4. Open `schema.sql` from this repository, copy its SQL, paste it into the SQL Editor, and run it. This creates a fresh `users` table.
5. Click **Connect** in Neon and copy the PostgreSQL connection string. Keep `sslmode=require` in it. Use the pooled connection string for the web app if available.

## 2. Push this folder to GitHub

Create a new GitHub repository. Upload the *contents* of this folder to the repository root (so `Dockerfile`, `index.html`, and `db.php` are at the top level).

Or, from a terminal opened inside this folder:

```bash
git init
git add .
git commit -m "Prepare PHP login app for Render and Neon"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/YOUR-REPOSITORY.git
git push -u origin main
```

Replace the remote URL with your own GitHub repository URL.

## 3. Deploy on Render

1. Open https://dashboard.render.com/ and choose **New + → Web Service**.
2. Connect GitHub and select the repository you just created.
3. Render should detect the `Dockerfile`. Choose the Docker runtime if asked.
4. Choose a service name and a plan. The Dockerfile configures Apache to listen on Render's `PORT` (default `10000`).
5. Under **Environment**, add:
   - Key: `DATABASE_URL`
   - Value: paste the connection string from your NEW Neon project.
6. Click **Create Web Service** and wait for the first deploy to finish.

## 4. Test registration and login

1. Open the `onrender.com` URL shown on the Render service page.
2. Click **Register**, enter a name, email, and password of at least 8 characters.
3. Submit the registration form. Then go back and sign in using the same email and password.
4. In Neon, open **SQL Editor** and run:

```sql
SELECT id, fullname, email, created_at FROM users ORDER BY id DESC;
```

You should see the newly registered user. Passwords are stored as hashes and are not displayed by this query.

## Important

- Do not add your Neon connection string to `db.php`, GitHub, or a committed `.env` file. Keep it only in Render's environment variables (and in an ignored local `.env` for local testing).
- This project uses only the new Neon database configured by `DATABASE_URL`; it does not migrate or copy data from the old local database.
- If the old password shown in your original uploaded `db.php` is a real active credential, change it in the old database as a precaution.
