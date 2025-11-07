

-- ============================================
-- Base de Datos: OtorrinoNet
-- Dr. Alejandro Viveros Domínguez
-- Sistema de Gestión de Consultorio Médico
-- PostgreSQL 16.1
-- ============================================

-- Crear la base de datos (ejecutar como superusuario)
-- CREATE DATABASE otorrinonet_db
--     WITH 
--     OWNER = drviverosorl
--     ENCODING = 'UTF8'
--     LC_COLLATE = 'es_MX.UTF-8'
--     LC_CTYPE = 'es_MX.UTF-8'
--     TABLESPACE = pg_default
--     CONNECTION LIMIT = -1;

-- Conectarse a la base de datos
\c otorrinonet_db;

-- ============================================
-- TABLA: users (Usuarios del sistema - Admin)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(150) NOT NULL,
    rol VARCHAR(20) DEFAULT 'admin' CHECK (rol IN ('admin', 'recepcionista', 'doctor')),
    activo BOOLEAN DEFAULT TRUE,
    ultimo_acceso TIMESTAMP,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para users
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_activo ON users(activo);

-- ============================================
-- TABLA: appointments (Citas Médicas)
-- ============================================
CREATE TABLE IF NOT EXISTS appointments (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha_cita DATE NOT NULL,
    hora_cita TIME NOT NULL,
    tipo_consulta VARCHAR(50) NOT NULL CHECK (tipo_consulta IN ('primera_vez', 'seguimiento', 'urgencia', 'valoracion_cirugia')),
    motivo TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'pendiente' CHECK (status IN ('pendiente', 'confirmada', 'completada', 'cancelada', 'no_asistio')),
    notas_internas TEXT,
    recordatorio_enviado BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_confirmacion TIMESTAMP,
    fecha_cancelacion TIMESTAMP,
    motivo_cancelacion TEXT,
    usuario_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    
    -- Constraint para evitar citas duplicadas
    CONSTRAINT unique_appointment UNIQUE(fecha_cita, hora_cita)
);

-- Índices para appointments
CREATE INDEX idx_appointments_fecha_cita ON appointments(fecha_cita);
CREATE INDEX idx_appointments_status ON appointments(status);
CREATE INDEX idx_appointments_email ON appointments(email);
CREATE INDEX idx_appointments_telefono ON appointments(telefono);
CREATE INDEX idx_appointments_fecha_hora ON appointments(fecha_cita, hora_cita);

-- ============================================
-- TABLA: services (Servicios Médicos)
-- ============================================
CREATE TABLE IF NOT EXISTS services (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    categoria VARCHAR(50) NOT NULL CHECK (categoria IN ('consulta', 'cirugia', 'estetica', 'tratamiento', 'otros')),
    duracion_minutos INTEGER DEFAULT 30,
    precio DECIMAL(10, 2),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para services
CREATE INDEX idx_services_categoria ON services(categoria);
CREATE INDEX idx_services_activo ON services(activo);

-- Datos iniciales de servicios
INSERT INTO services (nombre, descripcion, categoria, duracion_minutos, precio) VALUES
('Consulta Otorrinolaringológica', 'Evaluación completa del oído, nariz y garganta', 'consulta', 30, 800.00),
('Amigdalectomía', 'Extirpación quirúrgica de las amígdalas', 'cirugia', 60, 15000.00),
('Septoplastia', 'Corrección de desviación del tabique nasal', 'cirugia', 90, 25000.00),
('Rinoplastia', 'Cirugía estética y funcional de nariz', 'estetica', 120, 45000.00),
('Otoplastia', 'Corrección de orejas prominentes', 'estetica', 90, 35000.00),
('Tratamiento de Vértigo', 'Manejo especializado de trastornos del equilibrio', 'tratamiento', 45, 1200.00),
('Terapia de Voz', 'Rehabilitación vocal', 'tratamiento', 45, 1000.00),
('Adaptación Auditiva', 'Selección y adaptación de auxiliares auditivos', 'tratamiento', 60, 1500.00);

-- ============================================
-- TABLA: contact_messages (Mensajes de Contacto)
-- ============================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    asunto VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'nuevo' CHECK (status IN ('nuevo', 'leido', 'respondido', 'archivado')),
    notas_respuesta TEXT,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_lectura TIMESTAMP,
    fecha_respuesta TIMESTAMP,
    usuario_id INTEGER REFERENCES users(id) ON DELETE SET NULL
);

-- Índices para contact_messages
CREATE INDEX idx_contact_status ON contact_messages(status);
CREATE INDEX idx_contact_fecha_envio ON contact_messages(fecha_envio);
CREATE INDEX idx_contact_email ON contact_messages(email);

-- ============================================
-- TABLA: patients (Pacientes - Expedientes)
-- ============================================
CREATE TABLE IF NOT EXISTS patients (
    id SERIAL PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    fecha_nacimiento DATE,
    edad INTEGER,
    sexo VARCHAR(10) CHECK (sexo IN ('masculino', 'femenino', 'otro')),
    email VARCHAR(100),
    telefono VARCHAR(20) NOT NULL,
    telefono_emergencia VARCHAR(20),
    direccion TEXT,
    ocupacion VARCHAR(100),
    estado_civil VARCHAR(20),
    grupo_sanguineo VARCHAR(5),
    alergias TEXT,
    enfermedades_cronicas TEXT,
    medicamentos_actuales TEXT,
    cirugias_previas TEXT,
    antecedentes_familiares TEXT,
    notas_medicas TEXT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para patients
CREATE INDEX idx_patients_nombre ON patients(nombre_completo);
CREATE INDEX idx_patients_email ON patients(email);
CREATE INDEX idx_patients_telefono ON patients(telefono);
CREATE INDEX idx_patients_activo ON patients(activo);

-- ============================================
-- TABLA: medical_records (Expedientes Médicos)
-- ============================================
CREATE TABLE IF NOT EXISTS medical_records (
    id SERIAL PRIMARY KEY,
    patient_id INTEGER NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    fecha_consulta DATE NOT NULL,
    motivo_consulta TEXT NOT NULL,
    sintomas TEXT,
    diagnostico TEXT,
    tratamiento TEXT,
    medicamentos_recetados TEXT,
    estudios_solicitados TEXT,
    observaciones TEXT,
    proxima_cita DATE,
    doctor_nombre VARCHAR(150) DEFAULT 'Dr. Alejandro Viveros Domínguez',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para medical_records
CREATE INDEX idx_medical_patient ON medical_records(patient_id);
CREATE INDEX idx_medical_fecha ON medical_records(fecha_consulta);

-- ============================================
-- TABLA: schedule_config (Configuración de Horarios)
-- ============================================
CREATE TABLE IF NOT EXISTS schedule_config (
    id SERIAL PRIMARY KEY,
    dia_semana INTEGER NOT NULL CHECK (dia_semana BETWEEN 0 AND 6), -- 0=Domingo, 6=Sábado
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraint para evitar horarios duplicados
    CONSTRAINT unique_schedule UNIQUE(dia_semana, hora_inicio, hora_fin)
);

-- Datos iniciales de horarios
INSERT INTO schedule_config (dia_semana, hora_inicio, hora_fin, activo) VALUES
(1, '16:00', '20:00', TRUE), -- Lunes
(2, '16:00', '20:00', TRUE), -- Martes
(3, '16:00', '20:00', TRUE), -- Miércoles
(4, '10:00', '13:00', TRUE), -- Jueves
(5, '10:00', '13:00', TRUE); -- Viernes

-- ============================================
-- TABLA: blocked_dates (Fechas Bloqueadas)
-- ============================================
CREATE TABLE IF NOT EXISTS blocked_dates (
    id SERIAL PRIMARY KEY,
    fecha DATE NOT NULL UNIQUE,
    motivo VARCHAR(200),
    todo_el_dia BOOLEAN DEFAULT TRUE,
    hora_inicio TIME,
    hora_fin TIME,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índice para blocked_dates
CREATE INDEX idx_blocked_fecha ON blocked_dates(fecha);

-- ============================================
-- TABLA: audit_log (Registro de Auditoría)
-- ============================================
CREATE TABLE IF NOT EXISTS audit_log (
    id SERIAL PRIMARY KEY,
    tabla VARCHAR(50) NOT NULL,
    accion VARCHAR(20) NOT NULL CHECK (accion IN ('INSERT', 'UPDATE', 'DELETE')),
    registro_id INTEGER,
    datos_anteriores JSONB,
    datos_nuevos JSONB,
    usuario_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para audit_log
CREATE INDEX idx_audit_tabla ON audit_log(tabla);
CREATE INDEX idx_audit_fecha ON audit_log(fecha_accion);
CREATE INDEX idx_audit_usuario ON audit_log(usuario_id);

-- ============================================
-- VISTAS ÚTILES
-- ============================================

-- Vista de citas del día
CREATE OR REPLACE VIEW v_citas_hoy AS
SELECT 
    a.id,
    a.nombre,
    a.telefono,
    a.hora_cita,
    a.tipo_consulta,
    a.status,
    a.motivo
FROM appointments a
WHERE a.fecha_cita = CURRENT_DATE
ORDER BY a.hora_cita;

-- Vista de citas pendientes
CREATE OR REPLACE VIEW v_citas_pendientes AS
SELECT 
    a.id,
    a.nombre,
    a.email,
    a.telefono,
    a.fecha_cita,
    a.hora_cita,
    a.tipo_consulta,
    a.motivo,
    a.fecha_creacion
FROM appointments a
WHERE a.status = 'pendiente' 
AND a.fecha_cita >= CURRENT_DATE
ORDER BY a.fecha_cita, a.hora_cita;

-- Vista de mensajes sin leer
CREATE OR REPLACE VIEW v_mensajes_sin_leer AS
SELECT 
    cm.id,
    cm.nombre,
    cm.email,
    cm.telefono,
    cm.asunto,
    cm.mensaje,
    cm.fecha_envio
FROM contact_messages cm
WHERE cm.status = 'nuevo'
ORDER BY cm.fecha_envio DESC;

-- ============================================
-- FUNCIONES
-- ============================================

-- Función para actualizar timestamp de actualización
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Triggers para actualizar automáticamente fecha_actualizacion
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_appointments_updated_at BEFORE UPDATE ON appointments
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_services_updated_at BEFORE UPDATE ON services
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_patients_updated_at BEFORE UPDATE ON patients
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_medical_records_updated_at BEFORE UPDATE ON medical_records
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_schedule_config_updated_at BEFORE UPDATE ON schedule_config
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Función para verificar disponibilidad de horario
CREATE OR REPLACE FUNCTION check_appointment_availability(
    p_fecha DATE,
    p_hora TIME
)
RETURNS BOOLEAN AS $$
DECLARE
    v_count INTEGER;
    v_dia_semana INTEGER;
    v_schedule_exists BOOLEAN;
BEGIN
    -- Verificar si ya existe una cita en esa fecha/hora
    SELECT COUNT(*) INTO v_count
    FROM appointments
    WHERE fecha_cita = p_fecha
    AND hora_cita = p_hora
    AND status != 'cancelada';
    
    IF v_count > 0 THEN
        RETURN FALSE;
    END IF;
    
    -- Verificar si la fecha está bloqueada
    IF EXISTS (SELECT 1 FROM blocked_dates WHERE fecha = p_fecha) THEN
        RETURN FALSE;
    END IF;
    
    -- Verificar si hay horario configurado para ese día
    v_dia_semana := EXTRACT(DOW FROM p_fecha);
    
    SELECT EXISTS (
        SELECT 1 FROM schedule_config
        WHERE dia_semana = v_dia_semana
        AND hora_inicio <= p_hora
        AND hora_fin >= p_hora
        AND activo = TRUE
    ) INTO v_schedule_exists;
    
    RETURN v_schedule_exists;
END;
$$ LANGUAGE plpgsql;

-- Función para obtener horarios disponibles de un día
CREATE OR REPLACE FUNCTION get_available_times(p_fecha DATE)
RETURNS TABLE (hora TIME) AS $$
DECLARE
    v_dia_semana INTEGER;
    v_hora_inicio TIME;
    v_hora_fin TIME;
    v_hora_actual TIME;
BEGIN
    v_dia_semana := EXTRACT(DOW FROM p_fecha);
    
    -- Obtener configuración de horario del día
    FOR v_hora_inicio, v_hora_fin IN
        SELECT hora_inicio, hora_fin
        FROM schedule_config
        WHERE dia_semana = v_dia_semana
        AND activo = TRUE
    LOOP
        v_hora_actual := v_hora_inicio;
        
        -- Generar slots de 30 minutos
        WHILE v_hora_actual < v_hora_fin LOOP
            IF check_appointment_availability(p_fecha, v_hora_actual) THEN
                hora := v_hora_actual;
                RETURN NEXT;
            END IF;
            
            v_hora_actual := v_hora_actual + INTERVAL '30 minutes';
        END LOOP;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- ============================================
-- PERMISOS Y SEGURIDAD
-- ============================================

-- Revocar todos los permisos públicos
REVOKE ALL ON ALL TABLES IN SCHEMA public FROM PUBLIC;
REVOKE ALL ON ALL SEQUENCES IN SCHEMA public FROM PUBLIC;
REVOKE ALL ON ALL FUNCTIONS IN SCHEMA public FROM PUBLIC;

-- Otorgar permisos al usuario de la aplicación
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO drviverosorl;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO drviverosorl;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO drviverosorl;

-- ============================================
-- DATOS DE PRUEBA (Opcional - Comentar en producción)
-- ============================================

-- Usuario administrador de prueba (password: Admin123!)
-- NOTA: En producción, usar bcrypt o similar para hashear
INSERT INTO users (username, email, password_hash, nombre_completo, rol) VALUES
('admin', 'admin@otorrinonet.com', '$2y$10$rN9x8vX4kF5Z9vX4kF5Z9uF5Z9vX4kF5Z9vX4kF5Z9vX4kF5Z9vX4k', 'Administrador Sistema', 'admin');

-- ============================================
-- COMENTARIOS EN TABLAS
-- ============================================

COMMENT ON TABLE users IS 'Usuarios del sistema administrativo';
COMMENT ON TABLE appointments IS 'Citas médicas agendadas';
COMMENT ON TABLE services IS 'Catálogo de servicios médicos';
COMMENT ON TABLE contact_messages IS 'Mensajes del formulario de contacto';
COMMENT ON TABLE patients IS 'Expedientes de pacientes';
COMMENT ON TABLE medical_records IS 'Historial médico de consultas';
COMMENT ON TABLE schedule_config IS 'Configuración de horarios de atención';
COMMENT ON TABLE blocked_dates IS 'Fechas bloqueadas (vacaciones, días festivos)';
COMMENT ON TABLE audit_log IS 'Registro de auditoría del sistema';

-- ============================================
-- FIN DEL SCRIPT
-- ============================================

-- Verificar la creación de tablas
SELECT table_name 
FROM information_schema.tables 
WHERE table_schema = 'public';