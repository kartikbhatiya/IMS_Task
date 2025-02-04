CREATE TABLE categories (
    id BIGSERIAL PRIMARY KEY,  -- Use BIGSERIAL
    name VARCHAR(255) NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by BIGINT NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE,
    updated_by BIGINT,
    deleted_at TIMESTAMP WITH TIME ZONE,
    deleted_by BIGINT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id),
    FOREIGN KEY (deleted_by) REFERENCES users(id)
);

CREATE TABLE molecules (
    id BIGSERIAL PRIMARY KEY,  
    slug VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by BIGINT NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE,
    updated_by BIGINT,
    deleted_at TIMESTAMP WITH TIME ZONE,
    deleted_by BIGINT,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id),
    FOREIGN KEY (deleted_by) REFERENCES users(id)
);

CREATE TABLE product_molecules (
	id BIGSERIAL PRIMARY KEY,
    product_code VARCHAR(255) NOT NULL,
    molecule_id BIGINT NOT NULL,
	is_deleted BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by BIGINT NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE,
    updated_by BIGINT,
    deleted_at TIMESTAMP WITH TIME ZONE,
    deleted_by BIGINT,
    FOREIGN KEY (product_code) REFERENCES draft_products(product_code),
    FOREIGN KEY (molecule_id) REFERENCES molecules(id)
)

CREATE TABLE draft_products (
    id BIGSERIAL PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    product_code VARCHAR(255) UNIQUE NOT NULL,
    manufacturer_name VARCHAR(255) NOT NULL,
    mrp DECIMAL(10, 2) NOT NULL,
    combination_string TEXT NOT NULL,
    category_id BIGINT NOT NULL,
    is_banned BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    is_discontinued BOOLEAN DEFAULT FALSE,
    is_assured BOOLEAN DEFAULT FALSE,
    is_refrigerated BOOLEAN DEFAULT FALSE,
    is_deleted BOOLEAN DEFAULT FALSE,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    created_by BIGINT NOT NULL,
    updated_at TIMESTAMP WITH TIME ZONE,
    updated_by BIGINT NULL,
    deleted_at TIMESTAMP WITH TIME ZONE,
    deleted_by BIGINT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id),
    FOREIGN KEY (deleted_by) REFERENCES users(id)
);