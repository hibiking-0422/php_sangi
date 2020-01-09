<div id="app">

  <div class="content">
    <span>{{ scrollY }}</span>
  </div>
  
</div>

<script>
new Vue({
  el: '#app',
  data: {
  	scrollY: 0
  },
  mounted() {
  	window.addEventListener('scroll', this.handleScroll);
  },
  methods: {
    handleScroll() {
  		this.scrollY = window.scrollY;
    }
  }
})
</script>

<style>
.content {
  min-height: 300vh;
  background-color: #000;
}

.content span{
  position: fixed;
  background-color: #fff;
}
</style>