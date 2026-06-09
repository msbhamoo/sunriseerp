# 🚀 Deployment & Version Control Guide

This document outlines the complete workflow for managing your code locally, pushing to GitHub, auto-deploying to Hostinger, and reverting changes in case of an error.

---

## 1. Initial Setup (One-Time Only)

Since your `.gitignore` is already configured to protect your `database.php` and `addons/`, run these commands in your terminal to link your local code to GitHub:

```powershell
cd c:\wamp64\www\school
git init
git add .
git commit -m "Initial commit of school project"
git branch -M main

# Replace 'your-github-username' with your actual username!
git remote add origin https://github.com/your-github-username/sunriseeduhub.git

git push -u origin main
```

---

## 2. Hostinger Auto-Deploy Setup (One-Time Only)

Once your code is on GitHub, configure Hostinger to automatically deploy new changes.

1. Go to your **Hostinger hPanel**.
2. Navigate to **Advanced > GIT**.
3. Under **Deploy from GitHub**, select your `sunriseeduhub` repository.
4. The **Branch** dropdown will now show `main`. Select it.
5. Click **Deploy**.
6. **Set up Auto-Deploy:** Hostinger will provide a **Webhook URL**. 
   - Go to your GitHub repository in your browser.
   - Go to **Settings > Webhooks > Add webhook**.
   - Paste the Hostinger Webhook URL into the "Payload URL" field.
   - Set Content type to `application/json`.
   - Click **Add webhook**.

*Result: Every time code is pushed to GitHub, Hostinger will automatically update your live site!*

---

## 3. Daily Workflow (Coding with Antigravity)

When you ask me (Antigravity) to write or modify code for you, here is how we will save and deploy those changes:

**Step 1: I write the code**
You ask me to fix a bug or add a feature, and I modify the files locally in `c:\wamp64\www\school`.

**Step 2: Testing**
You test the changes locally using your WAMP server (`http://localhost/school`).

**Step 3: Committing the Code**
Once you confirm the code works, you can ask me to commit it for you, or you can run:
```powershell
git add .
git commit -m "Brief description of what was changed"
```

**Step 4: Deploying to Live**
Run this command to send the code to GitHub:
```powershell
git push
```
*Because we set up the Webhook in Step 2, Hostinger will instantly pull this new code to your live server!*

---

## 4. 🚨 EMERGENCY: How to Revert Code if the Live Site Breaks

If a recent change breaks your live site, DO NOT PANIC. Version control allows you to instantly "go back in time".

### Scenario A: Reverting the most recent commit
If you just pushed a commit and the site broke, you can undo that specific commit.

1. Open your terminal:
```powershell
cd c:\wamp64\www\school
```
2. Revert the last commit:
```powershell
git revert HEAD
```
*(This creates a new commit that exactly undoes the previous changes. A text editor might pop up asking you to save the commit message. Just save and close it).*

3. Push the fix to the live server:
```powershell
git push
```
*Hostinger will auto-deploy, and your site will be restored!*

### Scenario B: Restoring an older, specific working version
If you want to go back to a version from a few days ago:

1. View your history to find the ID of the working commit:
```powershell
git log --oneline
```
*(You will see a list like: `a1b2c3d Added new fee feature`)*

2. Copy the 7-character ID of the commit that WORKED.
3. Reset your code back to that exact point (Replace `a1b2c3d` with your ID):
```powershell
git reset --hard a1b2c3d
```

4. Force push the old working code to GitHub (WARNING: This rewrites history on the server):
```powershell
git push --force
```
*Hostinger will sync this older version, instantly fixing the live site.*
