@extends('layouts.app')

@section('title', 'Gestión de Productos')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 class="text-center">Gestión de Productos</h2>
        <form id="productoForm" class="mb-4">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre del Producto" required>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" id="precio" placeholder="Precio" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success w-100">Agregar Producto</button>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="productosTable">
                <!-- Aquí se cargarán los productos dinámicamente -->
            </tbody>
        </table>
    </div>
</div>

<script>
// Función para cargar productos desde la API
async function cargarProductos() {
    const response = await fetch('/api/productos', {
        headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
    });

    if (response.ok) {
        const productos = await response.json();
        let html = '';
        productos.forEach(prod => {
            html += `
                <tr>
                    <td>${prod.nombre}</td>
                    <td>${prod.precio}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="editarProducto(${prod.id}, '${prod.nombre}', ${prod.precio})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${prod.id})">Eliminar</button>
                    </td>
                </tr>`;
        });
        document.getElementById('productosTable').innerHTML = html;
    } else {
        alert('Error al cargar los productos');
    }
}

// Función para agregar un producto
document.getElementById('productoForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const nombre = document.getElementById('nombre').value;
    const precio = document.getElementById('precio').value;

    const response = await fetch('/api/productos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        body: JSON.stringify({ nombre, precio })
    });

    if (response.ok) {
        document.getElementById('successMessage').textContent = 'Producto agregado correctamente.';
        document.getElementById('productoForm').reset();
        cargarProductos(); // Recargar la lista de productos
    } else {
        alert('Error al agregar el producto');
    }
});

// Función para editar un producto
function editarProducto(id, nombre, precio) {
    document.getElementById('nombre').value = nombre;
    document.getElementById('precio').value = precio;

    const form = document.getElementById('productoForm');
    form.onsubmit = async function(event) {
        event.preventDefault();

        const nuevoNombre = document.getElementById('nombre').value;
        const nuevoPrecio = document.getElementById('precio').value;

        const response = await fetch(`/api/productos/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            body: JSON.stringify({ nombre: nuevoNombre, precio: nuevoPrecio })
        });

        if (response.ok) {
            document.getElementById('successMessage').textContent = 'Producto actualizado correctamente.';
            form.reset();
            form.onsubmit = agregarProducto; // Restaurar el evento original
            cargarProductos(); // Recargar la lista de productos
        } else {
            alert('Error al actualizar el producto');
        }
    };
}

// Función para eliminar un producto
async function eliminarProducto(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        const response = await fetch(`/api/productos/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        if (response.ok) {
            alert('Producto eliminado correctamente.');
            cargarProductos(); // Recargar la lista de productos
        } else {
            alert('Error al eliminar el producto');
        }
    }
}

// Cargar productos al cargar la página
cargarProductos();
</script>
@endsection
