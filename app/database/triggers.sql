DROP TRIGGER IF EXISTS update_comments_count ON comments;
DROP TRIGGER IF EXISTS update_comments_count_on_soft_delete ON comments;
DROP TRIGGER IF EXISTS update_posts_count ON posts;
DROP TRIGGER IF EXISTS update_posts_count_on_soft_delete ON posts;
DROP TRIGGER IF EXISTS update_users_on_soft_delete ON users;
DROP TRIGGER IF EXISTS update_users_subscriptions_on_insert ON users;

CREATE OR REPLACE FUNCTION update_comment_counts()
  RETURNS TRIGGER AS $$
BEGIN
  UPDATE categories SET comments_count = (
    SELECT count(*) AS AGGREGATE FROM comments INNER JOIN posts
        on posts.id = comments.post_id
    WHERE posts.category_id = categories.id AND comments.deleted_at ISNULL
  ) WHERE categories.parent_id NOTNULL;
  return new;
END
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_posts_counts()
  RETURNS TRIGGER AS $$
BEGIN
  UPDATE categories SET posts_count = (
    SELECT count(*) AS AGGREGATE FROM posts WHERE posts.category_id = categories.id AND posts.deleted_at ISNULL
  ) WHERE categories.parent_id NOTNULL;
  return new;
END
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_comments_count
  AFTER INSERT ON comments
  FOR EACH ROW
  EXECUTE PROCEDURE update_comment_counts();

CREATE TRIGGER update_comments_count_on_soft_delete
  AFTER UPDATE ON comments
--   FOR EACH ROW
--   WHEN (new.deleted_at IS NOT NULL)
  EXECUTE PROCEDURE update_comment_counts();

CREATE TRIGGER update_posts_count
  AFTER INSERT ON posts
--   FOR EACH ROW
  EXECUTE PROCEDURE update_posts_counts();

CREATE TRIGGER update_posts_count_on_soft_delete
  AFTER UPDATE ON posts
--   FOR EACH ROW
--   WHEN (new.deleted_at IS NOT NULL)
  EXECUTE PROCEDURE update_posts_counts();

CREATE TRIGGER update_users_on_soft_delete
  AFTER UPDATE ON users
  FOR EACH ROW
  WHEN (new.deleted_at IS NOT NULL)
  EXECUTE PROCEDURE update_user_relations();

CREATE TRIGGER update_users_subscriptions_on_insert
  AFTER INSERT ON users
  FOR EACH ROW
  EXECUTE PROCEDURE update_subscriptions();

CREATE OR REPLACE FUNCTION update_subscriptions()
  RETURNS TRIGGER as $$
BEGIN
  UPDATE categories SET subscriptions_count = (
    SELECT count(*) AS AGGREGATE FROM subscriptions WHERE subscriptions.category_id = categories.id
  ) WHERE categories.parent_id NOTNULL;
  return new;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_user_relations()
  RETURNS TRIGGER AS $$
BEGIN
--   UPDATE posts SET deleted_at = now() WHERE user_id = old.id;
--   UPDATE comments SET deleted_at = now() WHERE user_id = old.id;
  UPDATE images SET deleted_at = now() WHERE id IN (
    SELECT id from image_user INNER JOIN images ON images.id = image_user.image_id
    WHERE user_id = old.id and deleted_at ISNULL
  );
  UPDATE phones SET deleted_at = now() WHERE user_id = old.id;
  DELETE FROM push_tokens WHERE device_id IN (SELECT id FROM devices WHERE user_id = old.id);
  DELETE FROM devices CASCADE WHERE user_id = old.id;
  return old;
END;
$$ LANGUAGE plpgsql;

